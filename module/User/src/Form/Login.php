<?php
/**
 * File for Login Form Class
 *
 * @category  User
 * @package   User_Form
 * @author    Marco Neumann <webcoder_at_binware_dot_org>
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */

/**
 * @namespace
 */
namespace User\Form;

/**
 * @uses Zend\Form\Form
 */
use Zend\Form\Form;

/**
 * Login Form Class
 *
 * Login Form
 *
 * @category  User
 * @package   User_Form
 * @copyright Copyright (c) 2011, Marco Neumann
 * @license   http://binware.org/license/index/type:new-bsd New BSD License
 */
class Login extends Form
{
    /**
     * Initialize Form
     */
    public function init()
    {
        $this->setMethod('post')
             ->loadDefaultDecorators()
             ->addDecorator('FormErrors');

        $this->addElement(
            'text',
            'username',
            array(
                 'filters' => array(
                     array('StringTrim')
                 ),
                 'validators' => array(
                     'EmailAddress'
                 ),
                 'required' => true,
                 'label'    => 'Email'
            )
        );

        $this->addElement(
            'password',
            'password',
            array(
                 'filters' => array(
                     array('StringTrim')
                 ),
                 'validators' => array(
                     array(
                         'StringLength',
                         true,
                         array(
                             6,
                             999
                         )
                     )
                 ),
                 'required' => true,
                 'label'    => 'Password'
            )
        );

        $this->addElement(
            'hash',
            'csrf',
            array(
                 'ignore' => true,
                 'decorators' => array('ViewHelper')
            )
        );

        $this->addElement(
            'submit',
            'login',
            array(
                 'ignore' => true,
                 'label' => 'Login'
            )
        );

    }
}
