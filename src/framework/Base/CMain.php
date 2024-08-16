<?php

namespace Framework;

class CMain
{
    private $stylesLinks = [];
    public function includeComponent(string $component, string $template = ".default", array $arParams = [])
    {
        ob_start();

        $switch_env_variable = trim(Config::getInstance()->getEnv("CUSTOM_TEMPLATE"));
        $componentPath = $this->getComponentPath($component);

        if (!$componentPath) {
            throw new \Exception("Component file not found: " . $this->getDocumentRoot() . "/components/{$component}/component.php");
        }

        $templatePath = $this->getTemplatePath($component, $template, $switch_env_variable);

        global $arResult;
        include $componentPath;
        include $templatePath;

        $output = ob_get_clean();

        $stylesPath = dirname($templatePath) . "/style.css";
        $commonStylesPath = $this->getCommonStylesPath($switch_env_variable);

        $styles = file_exists($stylesPath) ? file_get_contents($stylesPath) : '';
        $commonStyles = file_exists($commonStylesPath) ? file_get_contents($commonStylesPath) : '';

        $output = $this->insertStylesInHead($output, $styles . $commonStyles);
        echo $output;
    }

    private function getComponentPath(string $component): ?string
    {
        $filePath = $this->getDocumentRoot() . "/components/{$component}/component.php";
        return file_exists($filePath) ? $filePath : null;
    }

    private function getTemplatePath(string $component, string $template, string $switch_env_variable): string
    {
        $customTemplatePath = $this->getDocumentRoot() . "/templates/{$switch_env_variable}/components/{$component}/templates/{$template}/template.php";
        $standardTemplatePath = $this->getDocumentRoot() . "/components/{$component}/templates/{$template}/template.php";

        if (file_exists($customTemplatePath)) {
            return $customTemplatePath;
        } elseif (file_exists($standardTemplatePath)) {
            return $standardTemplatePath;
        } else {
            throw new \Exception("Template file not found: {$customTemplatePath} or {$standardTemplatePath}");
        }
    }

    public function getDocumentRoot(): string
    {
        return $_SERVER["DOCUMENT_ROOT"];
    }

    private function getCommonStylesPath(string $switch_env_variable): string
    {
        $filePath = $this->getDocumentRoot() . "/templates/{$switch_env_variable}/css/main.css";
        return file_exists($filePath) ? $filePath : '';
    }

    public function insertStylesInHead(string $html, string $styles): string
    {
        if (!empty($styles)) {
            if (strpos($html, '</head>') !== false) {
                $html = preg_replace('/<\/head>/', "<style>{$styles}</style></head>", $html);
            } else {
                $html = "<style>{$styles}</style>" . $html;
            }
        }
        return $html;
    }

    public function processHeader(string $templateName, string $styles, $arParams): void
    {
        $documentRoot = $this->getDocumentRoot();

        $arResult = $arParams;
        $switch_env_variable = trim(Config::getInstance()->getEnv("CUSTOM_TEMPLATE"));

        $headerPath = "{$documentRoot}/templates/{$switch_env_variable}/header.php";

        if (!file_exists($headerPath)) {
            throw new \Exception("Header file not found: " . $headerPath);
        }

        ob_start();
        include $headerPath;
        $headerContent = ob_get_clean();
        $output = $this->insertStylesInHead($headerContent, $styles);

        echo $output;
    }

    public function processFooter(string $templateName, string $styles, $arParams): void
    {
        $documentRoot = $this->getDocumentRoot();
        $arResult = $arParams;
        $switch_env_variable = trim(Config::getInstance()->getEnv("CUSTOM_TEMPLATE"));
        $footerPath = "{$documentRoot}/templates/{$switch_env_variable}/footer.php";

        if (!file_exists($footerPath)) {
            throw new \Exception("Footer file not found: " . $footerPath);
        }

        ob_start();
        include $footerPath;
        $footerContent = ob_get_clean();
        $output = $footerContent;

        echo $output;
    }

    public function frontendConfig(): bool|string {
        return json_encode([
            "endpoint" => "/api.php"
        ]);
    }
}
