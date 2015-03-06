<?php
namespace Bolt\Extension\Jpissis\SlugWithCateg;

use Bolt\BaseExtension;

class Extension extends BaseExtension
{
	public function initialize()
	{
		$categories = $this->app['storage']->getTaxonomyType('categories');

		if (is_array($categories['options'])){
			foreach ($categories['options'] as $item) {
				$this->app->match(
					'/{contenttypeslug}/'.$item.'/{slug}', 
					"Bolt\Controllers\Frontend::record");
			}
		}

		$this->addTwigFilter('slugwithcateg', 'addCategIntoSlug');
	}

	/**
	 * Add the first category into the post's url
	 * 
	 * @param String $input the post's url
	 */
	function addCategIntoSlug($input) 
	{
    	$tab_input         = explode('/', $input);
    	$content_type_slug = $tab_input[1];
    	$slug              = $tab_input[2];

    	$content = $this->app['storage']->getContent(
    		$content_type_slug, 
    		array(
    			'slug' => $slug, 
    			'returnsingle' => true
    		)
    	);

    	$categories = $content->taxonomy['categories'];

    	if (is_array($categories)){
    		// get the first category
    		foreach ($categories as $categorie) {
    			$slug = '/'.$content_type_slug.'/'.$categorie.'/'.$slug;

    			break;
    		}
    	} else{
    		$slug = $input;
    	}    	

        return new \Twig_Markup($slug, 'UTF-8');
    }

	public function getName()
	{
		return "categorieinslug";
	}
}