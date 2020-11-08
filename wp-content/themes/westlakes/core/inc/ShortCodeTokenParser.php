<?php


namespace App;
use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use \Twig\Node\IncludeNode;
use \Twig\Token;

class ShortCodeTokenParser extends \Twig\TokenParser\IncludeTokenParser
{
	public function parse(Token $token)
	{
		$expr = $this->parser->getExpressionParser()->parseExpression();

		list($variables, $only, $ignoreMissing) = $this->parseArguments();

		$value =$expr->getAttribute('value');
		if ( empty( $variables ) ) {
			$variables = new ArrayExpression( [
				new ConstantExpression( 'name', 21 ),
				new ConstantExpression( $value, 21 )
			], 1 );
		} else {
			$variables->addElement( new ConstantExpression( $value, 21 ), new ConstantExpression( 'name', 21 ) );
		}

		$properties ="";
		array_map(function ($item) use (&$properties){
			if ($item['key']->getAttribute('value') !== 'name') {
				$properties .= $item['key']->getAttribute('value').'='.$item['value']->getAttribute('value'). ' ';
			}
		}, $variables->getKeyValuePairs());
		$variables->addElement( new ConstantExpression( $properties, 21 ), new ConstantExpression( 'properties', 21 ) );
		$expr->setAttribute( 'value', ['shortcode.twig'] );
		return new IncludeNode($expr, $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
	}

	public function getTag()
	{
		return 'shortcode';
	}
}
