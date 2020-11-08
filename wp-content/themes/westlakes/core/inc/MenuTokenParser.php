<?php


namespace App;
use Timber\Menu;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\IncludeNode;
use \Twig\Token;

class MenuTokenParser extends \Twig\TokenParser\IncludeTokenParser
{
	public function parse(Token $token)
	{
		$expr = $data = $this->parser->getExpressionParser()->parseExpression();
		list($variables, $only, $ignoreMissing) = $this->parseArguments();
		$value = $expr->getAttribute('value');

		if ( empty( $variables ) ) {
			$variables = new ArrayExpression( [
				new ConstantExpression( 'name', 21 ),
				new ConstantExpression( $value, 21 )
			], 1 );
		} else {
			$variables->addElement( new ConstantExpression( $value, 21 ), new ConstantExpression( 'name', 21 ) );
		}

		$expr->setAttribute( 'value', ['menu.twig']);

		return new IncludeNode($expr, $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
	}

	public function getTag()
	{
		return 'menu';
	}
}
