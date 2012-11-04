<?php

/* AlphaLemonThemeEngineBundle:Themes:index.html.twig */
class __TwigTemplate_5e2ed233be629c89c0a4fe630cea5572 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return $this->env->resolveTemplate($this->getContext($context, "base_template"));
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 18
        $context["domain"] = "al_themes";
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 20
    public function block_body($context, array $blocks = array())
    {
        // line 21
        echo "<div id=\"al_themes\">
    <table width=100% id=\"al_themes_table\">
      <tr id=\"al_themes_sections\">
        ";
        // line 24
        $template = $this->env->resolveTemplate($this->getContext($context, "panel_sections"));
        $template->display(array_merge($context, array("values" => $this->getContext($context, "values"))));
        // line 25
        echo "      </tr>
    </table>
</div>
";
    }

    public function getTemplateName()
    {
        return "AlphaLemonThemeEngineBundle:Themes:index.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 25,  34 => 24,  29 => 21,  26 => 20,  21 => 18,);
    }
}
