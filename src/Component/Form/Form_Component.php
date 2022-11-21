<?php

declare( strict_types=1 );

/**
 * Component for rendering a form
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @author Glynn Quelch <glynn.quelch@gmail.com>
 * @license http://www.opensource.org/licenses/mit-license.html  MIT License
 * @package PinkCrab\Form
 */

namespace PinkCrab\Form\Component\Form;

use PinkCrab\Form\Form;
use PinkCrab\Form\Utils;
use PinkCrab\Form\Builder\Element\Element;
use PinkCrab\Form\Component\Component_Factory;
use PinkCrab\Perique\Services\View\Component\Component;
use PinkCrab\Form\Component\Field\Abstract_Field_Component;
use PinkCrab\Form\Builder\Element\Field\Input\Abstract_Input;

class Form_Component extends Component {

	/** @var string */
	protected $key;

	/** @var string|null */
	protected $action;

	/** @var string|null */
	protected $method;

	/** @var string|null */
	protected $attributes;

	/** @var Component[] */
	protected $fields;

	/** @var array<string, int|float|string|bool|null|mixed[]> */
	protected $values;

	/** @var string|null */
	protected $nonce_action;

	public function __construct( Form $form ) {
		$this->key          = $form->get_key();
		$this->action       = $form->get_action();
		$this->method       = $form->get_method();
		$this->values       = $form->get_values();
		$this->nonce_action = $form->get_nonce_action();

		$this->set_fields( $form->get_fields() );
		$this->attributes = Utils::parse_attributes( $form->get_attributes() );
	}

	/**
	 * Set the form fields
	 *
	 * @param Element[] $fields
	 * @return void
	 */
	private function set_fields( array $fields ): void {

		// Iterate through fields and compose the Field Components based on type.
		$this->fields = array_map(
			function( Element $field ) {

				// If we have a value for this field, set it.
				if ( $field instanceof Abstract_Input && array_key_exists( $field->get_name(), $this->values ) ) {
					$value = $this->values[ $field->get_name() ];

					// If the field has a sanitizer, use it.
					if ( $field->has_sanitizer() ) {
						$value = $field->sanitize( $value ); 
					}

					// Set its value.
					$field->set_existing( $value );
				}

				return Component_Factory::instance()->from_element( $field );
			},
			$fields
		);
	}
}
