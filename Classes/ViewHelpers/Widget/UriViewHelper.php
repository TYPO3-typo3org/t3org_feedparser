<?php


/**
 * A view helper for creating URIs to extbase actions within widgets.
 *
 * = Examples =
 *
 * <code title="URI to the show-action of the current controller">
 * <f:widget.uri action="show" />
 * </code>
 * <output>
 * index.php?id=123&tx_myextension_plugin[widgetIdentifier][action]=show&tx_myextension_plugin[widgetIdentifier][controller]=Standard&cHash=xyz
 * (depending on the current page, widget and your TS configuration)
 * </output>
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License, version 3 or later
 * @api
 */
class Tx_T3orgFeedparser_ViewHelpers_Widget_UriViewHelper extends Tx_Fluid_ViewHelpers_Widget_UriViewHelper {

	/**
	 * Get the URI for an AJAX Request.
	 *
	 * @return string the AJAX URI
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	protected function getAjaxUri() {
		$action = $this->arguments['action'];
		$arguments = $this->arguments['arguments'];

		if ($action === NULL) {
			$action = $this->controllerContext->getRequest()->getControllerActionName();
		}
//		$arguments['id'] = $GLOBALS['TSFE']->id;
//		// TODO page type should be configurable
//		$arguments['type'] = 7076;
//		$arguments['fluid-widget-id'] = $this->controllerContext->getRequest()->getWidgetContext()->getAjaxWidgetIdentifier();
//		$arguments['action'] = $action;
//
//        return '?' . http_build_query($arguments, NULL, '&');

        $uriBuilder = $this->controllerContext->getUriBuilder();
        return $uriBuilder
            ->reset()
            ->setTargetPageUid($GLOBALS['TSFE']->id)
            ->setTargetPageType(7076)
            ->setArguments(array(
            'fluid-widget-id' => $this->controllerContext->getRequest()->getWidgetContext()->getAjaxWidgetIdentifier(),
            'action' => $action
        ))
            ->setSection($this->arguments['section'])
            ->setAddQueryString(TRUE)
            ->setArgumentsToBeExcludedFromQueryString(array('cHash'))
            ->setFormat($this->arguments['format'])
            ->build();
	}
}

?>