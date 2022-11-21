<?php

declare( strict_types=1 );

/**
 * Form Handler
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

namespace PinkCrab\Form\Handler;

use PinkCrab\Form\Form;
use PinkCrab\Form\Utils;
use Respect\Validation\Validator;
use PinkCrab\Form\Validation\Form_Validator;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Form\Builder\Element\Field_Traits\Sanitizer;

class Form_Handler {

	/** @var Form_Validator */
	private $validator;

	/** @var ServerRequestInterface */
	private $request;

	/** @var array<string, Validator> */
	private $rules = array();

	/** @var string[] */
	private $errors = array();

	/** @var array<string, mixed> */
	private $values = array();

	public function __construct( Form_Validator $validator, ServerRequestInterface $request ) {
		$this->validator = $validator;
		$this->request   = $request;
	}

	/**
	 * Checks if a form has been submitted.
	 *
	 * @param Form $form
	 * @return bool
	 */
	public function is_submitted( Form $form ): bool {
		return $form->get_key() === \sanitize_text_field( $this->find_value_in_request( 'pc_form_submitted' ) );
	}

	/**
	 * Handles the form
	 *
	 * @param Form $form
	 * @return self
	 */
	public function handle( Form $form ): self {

		$this->rules = $form->get_validation_rules();

		// If we have a nonce, add the rule.
		if ( null !== $form->get_nonce_action() ) {
			$this->rules[ $form->get_nonce_action() ] = Validator::nonce( $form->get_nonce_action() );
		}

		$this->errors = $this->validator->process_request( $this->request, $this->rules )
			->get_errors();
		$this->values = $this->sanitize_values( $form, $this->get_values_from_request( $form ) );

		return $this;
	}

	/**
	 * Get all the values from the request.
	 *
	 * @return array<string, mixed>
	 */
	protected function get_values_from_request( Form $form ): array {
		return array_map(
			array( $this, 'find_value_in_request' ),
			$form->get_field_names()
		);
	}

	/**
	 * Gets a value from the request, based on the method.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function find_value_in_request( string $key ) {
		return $this->request->getParsedBody()[ $key ] ?? null;
	}

	/**
	 * Sanitizes the values.
	 *
	 * @param Form $form
	 * @param array<string, mixed> $values
	 * @return array<string, mixed>
	 */
	public function sanitize_values( Form $form, array $values ): array {
		$v = array();
		foreach ( $values as $key => $value ) {
			$field = $form->get_field( $key );
			// If the field is not found, skip.
			if ( null === $field ) {
				continue;
			}

			$v[ $key ] = Utils::class_uses_trait( $field, Sanitizer::class )
				? $field->sanitize( $value ) // @phpstan-ignore-line, the conditional above ensures the method exists.
				: $value;
		}

		return $v;
	}

	/**
	 * Checks if there are any errors on the form.
	 *
	 * @return bool
	 */
	public function has_errors(): bool {
		return ! empty( $this->errors );
	}

	/**
	 * Gets the errors.
	 *
	 * @return array<string, mixed>
	 */
	public function get_errors(): array {
		return $this->errors;
	}

	/**
	 * Checks if there are any values.
	 *
	 * @return bool
	 */
	public function has_values(): bool {
		return ! empty( $this->values );
	}

	/**
	 * Gets the values.
	 *
	 * @return array<string, mixed>
	 */
	public function get_values(): array {
		return $this->values;
	}

	/**
	 * Gets the value for a specific key.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function get_value( string $key ) {
		return $this->values[ $key ] ?? null;
	}

	/**
	 * Checks if a value exists for a key.
	 *
	 * @param string $key
	 * @return bool
	 */
	public function has_value( string $key ): bool {
		return isset( $this->values[ $key ] );
	}

}
