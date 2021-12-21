<?php

/**
 * DomCommands.php - Provides DOM (HTML) related commands for the Response
 *
 * @author Thierry Feuzeu <thierry.feuzeu@gmail.com>
 * @license https://opensource.org/licenses/BSD-3-Clause BSD 3-Clause License
 * @link https://github.com/jaxon-php/jaxon-core
 */

namespace Jaxon\Response\Features;

trait DomCommands
{
    /**
     * Add a response command to the array of commands that will be sent to the browser
     *
     * @param string        $sName              The command name
     * @param array         $aAttributes        Associative array of attributes that will describe the command
     * @param mixed         $mData              The data to be associated with this command
     * @param boolean       $bRemoveEmpty       If true, remove empty attributes
     *
     * @return Response
     */
    abstract protected function _addCommand($sName, array $aAttributes, $mData, $bRemoveEmpty = false);

    /**
     * Add a command to assign the specified value to the given element's attribute
     *
     * @param string        $sTarget              The id of the html element on the browser
     * @param string        $sAttribute           The attribute to be assigned
     * @param string        $sData                The value to be assigned to the attribute
     *
     * @return Response
     */
    public function assign($sTarget, $sAttribute, $sData)
    {
        $aAttributes = [
            'id' => $sTarget,
            'prop' => $sAttribute
        ];
        return $this->_addCommand('as', $aAttributes, $sData);
    }

    /**
     * Add a command to assign the specified HTML content to the given element
     *
     * This is a shortcut for assign() on the innerHTML attribute.
     *
     * @param string        $sTarget              The id of the html element on the browser
     * @param string        $sData                The value to be assigned to the attribute
     *
     * @return Response
     */
    public function html($sTarget, $sData)
    {
        return $this->assign($sTarget, 'innerHTML', $sData);
    }

    /**
     * Add a command to append the specified data to the given element's attribute
     *
     * @param string        $sTarget            The id of the element to be updated
     * @param string        $sAttribute            The name of the attribute to be appended to
     * @param string        $sData                The data to be appended to the attribute
     *
     * @return Response
     */
    public function append($sTarget, $sAttribute, $sData)
    {
        $aAttributes = [
            'id' => $sTarget,
            'prop' => $sAttribute
        ];
        return $this->_addCommand('ap', $aAttributes, $sData);
    }

    /**
     * Add a command to prepend the specified data to the given element's attribute
     *
     * @param string        $sTarget            The id of the element to be updated
     * @param string        $sAttribute            The name of the attribute to be prepended to
     * @param string        $sData                The value to be prepended to the attribute
     *
     * @return Response
     */
    public function prepend($sTarget, $sAttribute, $sData)
    {
        $aAttributes = [
            'id' => $sTarget,
            'prop' => $sAttribute
        ];
        return $this->_addCommand('pp', $aAttributes, $sData);
    }

    /**
     * Add a command to replace a specified value with another value within the given element's attribute
     *
     * @param string        $sTarget            The id of the element to update
     * @param string        $sAttribute            The attribute to be updated
     * @param string        $sSearch            The needle to search for
     * @param string        $sData                The data to use in place of the needle
     *
     * @return Response
     */
    public function replace($sTarget, $sAttribute, $sSearch, $sData)
    {
        $aAttributes = [
            'id' => $sTarget,
            'prop' => $sAttribute
        ];
        $aData = [
            's' => $sSearch,
            'r' => $sData
        ];
        return $this->_addCommand('rp', $aAttributes, $aData);
    }

    /**
     * Add a command to clear the specified attribute of the given element
     *
     * @param string        $sTarget            The id of the element to be updated.
     * @param string        $sAttribute         The attribute to be cleared
     *
     * @return Response
     */
    public function clear($sTarget, $sAttribute = 'innerHTML')
    {
        return $this->assign($sTarget, $sAttribute, '');
    }

    /**
     * Add a command to assign a value to a member of a javascript object (or element)
     * that is specified by the context member of the request
     *
     * The object is referenced using the 'this' keyword in the sAttribute parameter.
     *
     * @param string        $sAttribute             The attribute to be updated
     * @param string        $sData                  The value to assign
     *
     * @return Response
     */
    public function contextAssign($sAttribute, $sData)
    {
        $aAttributes = ['prop' => $sAttribute];
        return $this->_addCommand('c:as', $aAttributes, $sData);
    }

    /**
     * Add a command to append a value onto the specified member of the javascript
     * context object (or element) specified by the context member of the request
     *
     * The object is referenced using the 'this' keyword in the sAttribute parameter.
     *
     * @param string        $sAttribute            The attribute to be appended to
     * @param string        $sData                The value to append
     *
     * @return Response
     */
    public function contextAppend($sAttribute, $sData)
    {
        $aAttributes = ['prop' => $sAttribute];
        return $this->_addCommand('c:ap', $aAttributes, $sData);
    }

    /**
     * Add a command to prepend the speicified data to the given member of the current
     * javascript object specified by context in the current request
     *
     * The object is access via the 'this' keyword in the sAttribute parameter.
     *
     * @param string        $sAttribute            The attribute to be updated
     * @param string        $sData                The value to be prepended
     *
     * @return Response
     */
    public function contextPrepend($sAttribute, $sData)
    {
        $aAttributes = ['prop' => $sAttribute];
        return $this->_addCommand('c:pp', $aAttributes, $sData);
    }

    /**
     * Add a command to to clear the value of the attribute specified in the sAttribute parameter
     *
     * The member is access via the 'this' keyword and can be used to update a javascript
     * object specified by context in the request parameters.
     *
     * @param string        $sAttribute            The attribute to be cleared
     *
     * @return Response
     */
    public function contextClear($sAttribute)
    {
        return $this->contextAssign($sAttribute, '');
    }

    /**
     * Add a command to remove an element from the document
     *
     * @param string        $sTarget            The id of the element to be removed
     *
     * @return Response
     */
    public function remove($sTarget)
    {
        $aAttributes = ['id' => $sTarget];
        return $this->_addCommand('rm', $aAttributes, '');
    }

    /**
     * Add a command to create a new element on the browser
     *
     * @param string        $sParent            The id of the parent element
     * @param string        $sTag                The tag name to be used for the new element
     * @param string        $sId                The id to assign to the new element
     *
     * @return Response
     */
    public function create($sParent, $sTag, $sId)
    {
        $aAttributes = [
            'id' => $sParent,
            'prop' => $sId
        ];
        return $this->_addCommand('ce', $aAttributes, $sTag);
    }

    /**
     * Add a command to insert a new element just prior to the specified element
     *
     * @param string        $sBefore            The id of the element used as a reference point for the insertion
     * @param string        $sTag               The tag name to be used for the new element
     * @param string        $sId                The id to assign to the new element
     *
     * @return Response
     */
    public function insertBefore($sBefore, $sTag, $sId)
    {
        $aAttributes = [
            'id' => $sBefore,
            'prop' => $sId
        ];
        return $this->_addCommand('ie', $aAttributes, $sTag);
    }

    /**
     * Add a command to insert a new element just prior to the specified element
     * This is an alias for insertBefore.
     *
     * @param string        $sBefore            The id of the element used as a reference point for the insertion
     * @param string        $sTag               The tag name to be used for the new element
     * @param string        $sId                The id to assign to the new element
     *
     * @return Response
     */
    public function insert($sBefore, $sTag, $sId)
    {
        return $this->insertBefore($sBefore, $sTag, $sId);
    }

    /**
     * Add a command to insert a new element after the specified
     *
     * @param string        $sAfter             The id of the element used as a reference point for the insertion
     * @param string        $sTag               The tag name to be used for the new element
     * @param string        $sId                The id to assign to the new element
     *
     * @return Response
     */
    public function insertAfter($sAfter, $sTag, $sId)
    {
        $aAttributes = [
            'id' => $sAfter,
            'prop' => $sId
        ];
        return $this->_addCommand('ia', $aAttributes, $sTag);
    }

    /**
     * Add a command to create an input element on the browser
     *
     * @param string        $sParent            The id of the parent element
     * @param string        $sType              The type of the new input element
     * @param string        $sName              The name of the new input element
     * @param string        $sId                The id of the new element
     *
     * @return Response
     */
    public function createInput($sParent, $sType, $sName, $sId)
    {
        $aAttributes = [
            'id' => $sParent,
            'prop' => $sId,
            'type' => $sType
        ];
        return $this->_addCommand('ci', $aAttributes, $sName);
    }

    /**
     * Add a command to insert a new input element preceding the specified element
     *
     * @param string        $sBefore            The id of the element to be used as the reference point for the insertion
     * @param string        $sType                The type of the new input element
     * @param string        $sName                The name of the new input element
     * @param string        $sId                The id of the new element
     *
     * @return Response
     */
    public function insertInput($sBefore, $sType, $sName, $sId)
    {
        $aAttributes = [
            'id' => $sBefore,
            'prop' => $sId,
            'type' => $sType
        ];
        return $this->_addCommand('ii', $aAttributes, $sName);
    }

    /**
     * Add a command to insert a new input element after the specified element
     *
     * @param string        $sAfter                The id of the element to be used as the reference point for the insertion
     * @param string        $sType                The type of the new input element
     * @param string        $sName                The name of the new input element
     * @param string        $sId                The id of the new element
     *
     * @return Response
     */
    public function insertInputAfter($sAfter, $sType, $sName, $sId)
    {
        $aAttributes = [
            'id' => $sAfter,
            'prop' => $sId,
            'type' => $sType
        ];
        return $this->_addCommand('iia', $aAttributes, $sName);
    }
}
