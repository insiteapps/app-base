<?php
    /**
     *
     * @copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
     * @package       insiteapps
     * @author        Patrick Chitovoro  <patrick@insiteapps.co.za>
     * All rights reserved. No warranty, explicit or implicit, provided.
     *
     * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if
     * any.
     * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and
     * may be covered by South African. and Foreign Patents, patents in process, and are protected by trade secret or
     * copyright laws. Dissemination of this information or reproduction of this material is strictly forbidden unless
     * prior written permission is obtained from Insite Apps. Proprietary and confidential. There is no freedom to use,
     * share or change this file.
     *
     *
     */

    namespace InsiteApps\BaseApp;


    use SilverStripe\Core\Config\Config;
    use SilverStripe\Dev\Debug;
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\Core\Manifest\ModuleLoader;
    use SilverStripe\View\SSViewer;
    use SilverStripe\View\ThemeResourceLoader;

    class PageExtension extends DataExtension
    {
        public function ThemeDir()
        {
            $loader = ThemeResourceLoader::inst();
            $themes = SSViewer::get_themes();
            $paths = $loader->getThemePaths($themes);
            $Theme_path = $paths[0];

            return $Theme_path;

        }
    }

    class PageControllerExtension extends DataExtension
    {

    }