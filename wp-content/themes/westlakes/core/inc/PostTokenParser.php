<?php


namespace App;

use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use \Twig\Node\IncludeNode;
use \Twig\Token;

class PostTokenParser extends \Twig\TokenParser\IncludeTokenParser
{
    public function parse(Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();

        list($variables, $only, $ignoreMissing) = $this->parseArguments();

        $value = $expr->getAttribute('value');
        if (empty($variables)) {
            $variables = new ArrayExpression([
                new ConstantExpression('name', 21),
                new ConstantExpression($value, 21)
            ], 1);
        } else {
            $variables->addElement(new ConstantExpression($value, 21), new ConstantExpression('name', 21));
        }
        if ($value) {
            $args = array(
                'name' => $value,
                'post_type'   => ['page', 'post'],
                'post_status' => 'publish',
                'numberposts' => 1
            );
            $post = \Timber::get_post($args);
            $context['content'] = $post->content;
            $context['name'] = $value;
            $htmlSidebar =  \Timber::compile(['post.twig'], $context);
            $variables->addElement(new ConstantExpression($htmlSidebar, 21), new ConstantExpression('content', 21));
        }
        $expr->setAttribute('value', ['post.twig']);
        return new IncludeNode($expr, $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return 'post';
    }
}
