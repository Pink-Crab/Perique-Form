<?php

declare( strict_types=1 );

/**
 * Form instance
 *
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

namespace PinkCrab\Form;

use Respect\Validation\Validator;
use PinkCrab\Form\Builder\Element\Field;
use Symfony\Component\VarDumper\Server\DumpServer;
use PinkCrab\Form\Builder\Style\{Style_Provider, Style};
use PinkCrab\Form\Builder\Element\Field_Traits\Sanitizer;
use function PinkCrab\FunctionConstructors\Objects\{usesTrait};
use PinkCrab\Form\Builder\Element\Field\Attribute\Notification;
use PinkCrab\Form\Builder\Element\Field_Traits\{Attributes, Fields, Wrapper_Attributes};

class Form {

	use Attributes, Fields, Wrapper_Attributes;

	/**
	 * The forms key.
	 *
	 * @var string
	 */
	protected $key;

	/**
	 * The form method
	 *
	 * @var string
	 */
	protected $method;

	/**
	 * The form action
	 *
	 * @var string
	 */
	protected $action;

	/**
	 * Form nonce action
	 *
	 * @var string|null
	 */
	protected $nonce_action;

	/**
	 * The form values.
	 *
	 * @var array<string, mixed>
	 */
	protected $values = array();

	/**
	 * The forms styling
	 *
	 * @var Style
	 */
	protected $form_style;

	/**
	 * Creates an instance of the form.
	 *
	 * @param string $key
	 * @param string $method
	 * @param string $action
	 */
	public function __construct( string $key, string $method = '', string $action = '' ) {
		$this->key          = $key;
		$this->method       = $method;
		$this->action       = $action;
		$this->nonce_action = $key . '_nonce';

		$this->form_style = Style_Provider::get_default_style();

		$this->id( "form_{$key}" );
		$this->add_class( $this->form_style->form_class() );

		// Add form based validation rules.
		$this->add_validation_rule( 'pc_form_submitted', Validator::equals( $this->key ) );
	}

	/**
	 * Get the form key
	 *
	 * @return string
	 */
	public function get_key(): string {
		return $this->key;
	}

	/**
	 * Get the form method
	 *
	 * @return string
	 */
	public function get_method(): string {
		return $this->method;
	}

	/**
	 * Get the form action
	 *
	 * @return string
	 */
	public function get_action(): string {
		return $this->action;
	}

	/**
	 * Set the nonce action
	 *
	 * @param string|null $nonce_action
	 * @return static
	 */
	public function nonce_action( ?string $nonce_action ): self {
		$this->nonce_action = $nonce_action;
		return $this;
	}

	/**
	 * Get the form nonce action.
	 *
	 * @return string|null
	 */
	public function get_nonce_action(): ?string {
		return $this->nonce_action;
	}

	/**
	 * Set the encoding type.
	 *
	 * @param string $enctype
	 * @return static
	 */
	public function enctype( ?string $enctype = null ): self {
		if ( null !== $enctype ) {
			$this->attribute( 'enctype', $enctype );
		} else {
			$this->remove_attribute( 'enctype' );
		}

		return $this;
	}

	/**
	 * Get the encoding type.
	 *
	 * @return ?string
	 */
	public function get_enctype(): ?string {
		return $this->has_attribute( 'enctype' )
			? esc_attr( (string) $this->get_attribute( 'enctype' ) )
			: null;
	}

	/**
	 * Set the target.
	 *
	 * @param string|null $target
	 * @return static
	 */
	public function target( ?string $target = null ): self {
		if ( null !== $target ) {
			$this->attribute( 'target', $target );
		} else {
			$this->remove_attribute( 'target' );
		}
		return $this;
	}

	/**
	 * Checks if the target is set.
	 *
	 * @return bool
	 */
	public function has_target(): bool {
		return $this->has_attribute( 'target' );
	}

	/**
	 * Get the target.
	 *
	 * @return ?string
	 */
	public function get_target(): ?string {
		return $this->has_attribute( 'target' )
			? esc_attr( (string) $this->get_attribute( 'target' ) )
			: null;
	}

	/**
	 * Set the autocomplete.
	 *
	 * @param ?string $autocomplete
	 * @return static
	 */
	public function autocomplete( ?string $autocomplete = null ): self {
		if ( null !== $autocomplete ) {
			$this->attribute( 'autocomplete', $autocomplete );
		} else {
			$this->remove_attribute( 'autocomplete' );
		}
		return $this;
	}

	/**
	 * Checks if the autocomplete is set.
	 *
	 * @return bool
	 */
	public function has_autocomplete(): bool {
		return $this->has_attribute( 'autocomplete' );
	}

	/**
	 * Get the autocomplete.
	 *
	 * @return ?string
	 */
	public function get_autocomplete(): ?string {
		return $this->has_attribute( 'autocomplete' )
			? esc_attr( (string) $this->get_attribute( 'autocomplete' ) )
			: null;
	}

	/**
	 * Add values
	 *
	 * @param array<string, mixed> $values
	 * @return static
	 */
	public function add_values( array $values ): self {
		foreach ( $values as $key => $value ) {
			$this->add_value( $key, $value );
		}
		return $this;
	}

	/**
	 * Adds a single value
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return static
	 */
	public function add_value( string $key, $value ): self {
		if ( $this->has_field( $key ) ) {
			$field = $this->get_field( $key );

			// Bail if the field is not a value field.
			if ( is_null( $field ) ) {
				return $this;
			}

			// Sanitize with fields sanitize method if exists.
			$this->values[ $key ] = Utils::class_uses_trait( $field, Sanitizer::class )
				? $field->sanitize( $value ) // @phpstan-ignore-line, the conditional above ensures the method exists.
				: $value;
		}
		return $this;
	}

	/**
	 * Checks if a value exists
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has_value( string $key ): bool {
		return array_key_exists( $key, $this->values );
	}

	/**
	 * Gets a value
	 *
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public function get_value( string $key, $default = null ) {
		return $this->has_value( $key )
			? $this->values[ $key ]
			: $default;
	}

	/**
	 * Gets all the values.
	 *
	 * @return array<string, mixed>
	 */
	public function get_values(): array {
		return $this->values;
	}

	/**
	 * Set novalidate.
	 *
	 * @param bool $novalidate
	 * @return static
	 */
	public function novalidate( bool $novalidate = true ): self {
		// If set as true, add the attribute.
		if ( $novalidate ) {
			$this->attribute( 'novalidate' );
		} else {
			// If set as false, remove the attribute.
			$this->remove_attribute( 'novalidate' );
		}
		return $this;
	}

	/**
	 * Checks if novalidate is set.
	 *
	 * @return bool
	 */
	public function is_novalidate(): bool {
		return $this->has_attribute( 'novalidate' );
	}

	/**
	 * Adds a form attribute.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @return static
	 * @deprecated Just use $this->attribute($key, $value) from attribute trait
	 */
	public function add_attribute( string $key, $value ): self {
		return $this->attribute( $key, $value );
	}

	/**
	 * Adds an array of attributes.
	 *
	 * @param array<string, mixed> $attributes
	 * @return static
	 * @deprecated Just use $this->attributes($attributes) from attribute trait
	 */
	public function add_attributes( array $attributes ): self {
		return $this->attributes( $attributes );
	}

	/**
	 * Adds errors to the form.
	 *
	 * @param array<string, string[]> $errors
	 * @return static
	 */
	public function add_errors( array $errors ): self {
		foreach ( $errors as $key => $element_errors ) {
			// Find the field.
			$field = $this->get_field( $key );
			// If the element has notifications/errors.
			if ( ! is_null( $field ) && \method_exists( $field, 'error_notification' ) ) {
				$field->error_notification( join( ', ', $element_errors ) );
			}
		}
		return $this;
	}



}
