<?php


namespace App;

use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConstantExpression;
use \Twig\Node\IncludeNode;
use \Twig\Token;

class SidebarTokenParser extends \Twig\TokenParser\IncludeTokenParser
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
        $name = !empty($value) ? $value : 'blog-sidebar';
        $sidebar = \Timber::get_widgets($name);
        $context['sidebar'] = $sidebar;
        $context['name'] = $name;
        $htmlSidebar =  \Timber::compile(['sidebars/' . $value . '.twig', 'sidebars/default.twig'], $context);

        $variables->addElement(new ConstantExpression($htmlSidebar, 21), new ConstantExpression('content', 21));
        $expr->setAttribute('value', 'sidebar.twig');
        return new IncludeNode($expr, $variables, $only, $ignoreMissing, $token->getLine(), $this->getTag());
    }

    public function getTag()
    {
        return 'sidebar';
    }
}
