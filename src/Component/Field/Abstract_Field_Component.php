<?php

declare( strict_types=1 );

/**
 * Bootstrap Component
 *
 * Abstract class for all bootstrap components.
 *
 * Handles attribute and class parsing.
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

namespace PinkCrab\Form\Component\Field;

use PinkCrab\Form\Utils;
use PinkCrab\Form\Builder\Element\Element;

use PinkCrab\Perique\Services\View\Component\Component;

abstract class Abstract_Field_Component extends Component {

	/** @var Element */
	protected $field;
	
	/** @var string|null  */
	protected $before_field = '';

	/** @var string|null  */
	protected $after_field = '';

	/** @var string  */
	protected $field_attributes;

	/** @var string */
	protected $wrapper_attributes;

	/**
	 * Returns the base attributes.
	 *
	 * @return array<string, string|int|float>
	 */
	protected function base_attributes(): array {
		return array();
	}

	/**
	 * Sets the attributes for the button group.
	 *
	 * @param array<string, string|int|float|null|bool> $attributes
	 * @return void
	 */
	protected function set_attributes( array $attributes ): void {

		$attributes = Utils::merge_arrays(
			array_map( 'strval', $this->base_attributes() ),
			array_map( 'strval', $attributes ),
			array( 'class' )
		);

		// $attributes = Utils::merge_arrays(
		// 	$base_attributes,
		// 	$this->field_attributes ?? array(),
		// 	array( 'class' )
		// );

		// Replace all defaults with attributes.
		$this->field_attributes = Utils::parse_attributes( $attributes );

		$this->set_wrapper_attributes();
	}

	/**
	 * Compile and set the wrapper attributes.
	 *
	 * @return void
	 */
	protected function set_wrapper_attributes(): void {
		// Add the ID if set.
		$attributes = array();

		// Add the ID if set.
		$attributes['id'] = esc_attr(
			$this->field->get_wrapper_id()
				? $this->field->get_wrapper_id()
				: "field_{$this->field->get_name()}_wrapper"
		);

		// Add the field type to class.
		$attributes['class'] = "field_{$this->field->get_type()}";

		// Add any additional classes
		if ( $this->field->get_wrapper_class() ) {
			$attributes['class'] .= " {$this->field->get_wrapper_class()}";
		}

		// Add the wrapper attributes.
		$this->wrapper_attributes = Utils::parse_attributes( $attributes + $this->field->get_wrapper_attributes() );
	}

	/**
	 * Set the value of before_field
	 *
	 * @param string $before_field
	 * @return self
	 */
	public function before_field( string $before_field ): self {
		$this->before_field = $before_field;
		return $this;
	}

	/**
	 * Set the value of after_field
	 *
	 * @param string $after_field
	 * @return self
	 */
	public function after_field( string $after_field ): self {
		$this->after_field = $after_field;
		return $this;
	}

}
