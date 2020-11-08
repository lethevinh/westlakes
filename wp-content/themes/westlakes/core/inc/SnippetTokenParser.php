<?php


namespace App;
use \Twig\Node\IncludeNode;
use \Twig\Token;

class SnippetTokenParser extends \Twig\TokenParser\IncludeTokenParser
{
	public function parse(Token $token)
	{
		$expr = $this->parser->getExpressionParser()->parseExpression();

		list($variables, $only, $ignoreMissing) = $this->parseArguments();
		$value =$expr->getAttribute('value');
		$expr->setAttribute( 'value', 'sections/' . $value . '.twig' );
		return new IncludeNode($expr, $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
	}

	public function getTag()
	{
		return 'snippet';
	}
}
