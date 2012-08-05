<?php

/* AlphaLemonCmsInstallerBundle::Installer/form.html.twig */
class __TwigTemplate_a979a0fe66a1df662d3291682ff11f71 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("form_div_layout.html.twig");

        $this->blocks = array(
            'field_rows' => array($this, 'block_field_rows'),
            'field_row' => array($this, 'block_field_row'),
            'field_label' => array($this, 'block_field_label'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "form_div_layout.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 18
    public function block_field_rows($context, array $blocks = array())
    {
        // line 19
        echo "    <div class=\"al-form-errors\">
        ";
        // line 20
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
        echo "
    </div>
    ";
        // line 22
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute($this->getContext($context, "form"), "children"));
        foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
            // line 23
            echo "        ";
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "child"), 'row');
            echo "
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
        $context = array_merge($_parent, array_intersect_key($context, $_parent));
    }

    // line 27
    public function block_field_row($context, array $blocks = array())
    {
        // line 28
        echo "    <div class=\"al-form-row\">
        ";
        // line 29
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'label');
        echo "
        <div class=\"al-form-field\">
            ";
        // line 31
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'widget');
        echo "
            <div class=\"al-form-errors\">
                ";
        // line 33
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock($this->getContext($context, "form"), 'errors');
        echo "
            </div>
        </div>
    </div>
";
    }

    // line 39
    public function block_field_label($context, array $blocks = array())
    {
        // line 40
        echo "    <label for=\"";
        echo twig_escape_filter($this->env, $this->getContext($context, "id"), "html", null, true);
        echo "\">
        ";
        // line 41
        echo twig_escape_filter($this->env, $this->env->getExtension('translator')->trans($this->getContext($context, "label")), "html", null, true);
        echo "
        ";
        // line 42
        if ($this->getContext($context, "required")) {
            // line 43
            echo "            <span class=\"al-form-required\" title=\"This field is required\">*</span>
        ";
        }
        // line 45
        echo "    </label>
";
    }

    public function getTemplateName()
    {
        return "AlphaLemonCmsInstallerBundle::Installer/form.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  97 => 45,  93 => 43,  87 => 41,  82 => 40,  79 => 39,  70 => 33,  65 => 31,  60 => 29,  57 => 28,  39 => 22,  28 => 18,  161 => 46,  156 => 45,  150 => 44,  146 => 40,  143 => 39,  133 => 36,  129 => 34,  126 => 33,  122 => 32,  119 => 31,  116 => 30,  112 => 29,  109 => 28,  105 => 27,  102 => 26,  91 => 42,  84 => 47,  81 => 46,  78 => 45,  76 => 44,  69 => 41,  67 => 39,  64 => 38,  61 => 37,  59 => 36,  56 => 35,  53 => 30,  43 => 23,  37 => 21,  33 => 20,  191 => 127,  185 => 124,  177 => 119,  173 => 118,  167 => 115,  163 => 114,  159 => 113,  152 => 109,  138 => 37,  132 => 95,  120 => 85,  114 => 82,  110 => 80,  108 => 79,  104 => 78,  99 => 25,  96 => 74,  54 => 27,  51 => 25,  44 => 27,  41 => 26,  34 => 20,  31 => 19,  26 => 15,);
    }
}
