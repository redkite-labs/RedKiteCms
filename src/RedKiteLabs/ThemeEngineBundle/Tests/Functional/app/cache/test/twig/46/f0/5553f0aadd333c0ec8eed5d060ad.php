<?php

/* AlphaLemonThemeEngineBundle:Theme:base.html.twig */
class __TwigTemplate_46f05553f0aadd333c0ec8eed5d060ad extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'title' => array($this, 'block_title'),
            'description' => array($this, 'block_description'),
            'keywords' => array($this, 'block_keywords'),
            'external_javascripts' => array($this, 'block_external_javascripts'),
            'external_stylesheets' => array($this, 'block_external_stylesheets'),
            'internal_header_javascripts' => array($this, 'block_internal_header_javascripts'),
            'internal_header_stylesheets' => array($this, 'block_internal_header_stylesheets'),
            'body' => array($this, 'block_body'),
            'internal_body_javascripts' => array($this, 'block_internal_body_javascripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 15
        echo "
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <title>";
        // line 20
        $this->displayBlock('title', $context, $blocks);
        echo "</title>
        <meta name=\"Description\" content=\"";
        // line 21
        $this->displayBlock('description', $context, $blocks);
        echo "\" />
        <meta name=\"Keywords\" content=\"";
        // line 22
        $this->displayBlock('keywords', $context, $blocks);
        echo "\" />
        <meta name=\"generator\" content=\"AlphaLemon CMS\" />
        ";
        // line 24
        $this->displayBlock('external_javascripts', $context, $blocks);
        // line 31
        echo "        ";
        $this->displayBlock('external_stylesheets', $context, $blocks);
        // line 38
        echo "        ";
        $this->displayBlock('internal_header_javascripts', $context, $blocks);
        // line 39
        echo "        ";
        $this->displayBlock('internal_header_stylesheets', $context, $blocks);
        // line 40
        echo "        <link rel=\"shortcut icon\" href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl("favicon.ico"), "html", null, true);
        echo "\" />
    </head>
    <body>
        ";
        // line 43
        $this->displayBlock('body', $context, $blocks);
        echo "        
        ";
        // line 44
        $this->displayBlock('internal_body_javascripts', $context, $blocks);
        // line 45
        echo "    </body>
</html>
";
    }

    // line 20
    public function block_title($context, array $blocks = array())
    {
        if (array_key_exists("metadescription", $context)) {
            echo twig_escape_filter($this->env, $this->getContext($context, "metatitle"), "html", null, true);
        }
    }

    // line 21
    public function block_description($context, array $blocks = array())
    {
        if (array_key_exists("metadescription", $context)) {
            echo twig_escape_filter($this->env, $this->getContext($context, "metadescription"), "html", null, true);
        }
    }

    // line 22
    public function block_keywords($context, array $blocks = array())
    {
        if (array_key_exists("metakeywords", $context)) {
            echo twig_escape_filter($this->env, $this->getContext($context, "metakeywords"), "html", null, true);
        }
    }

    // line 24
    public function block_external_javascripts($context, array $blocks = array())
    {
        // line 25
        echo "            ";
        if (array_key_exists("javascripts", $context)) {
            // line 26
            echo "                ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "javascripts"));
            foreach ($context['_seq'] as $context["_key"] => $context["javascript"]) {
                // line 27
                echo "                    <script src=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl($this->getContext($context, "javascript")), "html", null, true);
                echo "\" type=\"text/javascript\"></script>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['javascript'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 29
            echo "            ";
        }
        // line 30
        echo "        ";
    }

    // line 31
    public function block_external_stylesheets($context, array $blocks = array())
    {
        echo "            
            ";
        // line 32
        if (array_key_exists("stylesheets", $context)) {
            // line 33
            echo "                ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getContext($context, "stylesheets"));
            foreach ($context['_seq'] as $context["_key"] => $context["stylesheet"]) {
                // line 34
                echo "                    <link href=\"";
                echo twig_escape_filter($this->env, $this->env->getExtension('assets')->getAssetUrl($this->getContext($context, "stylesheet")), "html", null, true);
                echo "\" type=\"text/css\" rel=\"stylesheet\" />
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['stylesheet'], $context['_parent'], $context['loop']);
            $context = array_merge($_parent, array_intersect_key($context, $_parent));
            // line 36
            echo "            ";
        }
        // line 37
        echo "        ";
    }

    // line 38
    public function block_internal_header_javascripts($context, array $blocks = array())
    {
    }

    // line 39
    public function block_internal_header_stylesheets($context, array $blocks = array())
    {
    }

    // line 43
    public function block_body($context, array $blocks = array())
    {
    }

    // line 44
    public function block_internal_body_javascripts($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "AlphaLemonThemeEngineBundle:Theme:base.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  170 => 44,  165 => 43,  160 => 39,  155 => 38,  151 => 37,  148 => 36,  139 => 34,  134 => 33,  132 => 32,  127 => 31,  123 => 30,  120 => 29,  111 => 27,  106 => 26,  103 => 25,  100 => 24,  92 => 22,  84 => 21,  76 => 20,  70 => 45,  68 => 44,  64 => 43,  57 => 40,  54 => 39,  51 => 38,  48 => 31,  46 => 24,  41 => 22,  33 => 20,  37 => 21,  34 => 24,  29 => 21,  26 => 15,  21 => 18,);
    }
}
