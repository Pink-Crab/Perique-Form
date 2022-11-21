<?php

declare( strict_types=1 );

/**
 * Input text field
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

namespace PinkCrab\Form\Builder\Element\Field\Input;

use function PinkCrab\FunctionConstructors\Strings as Str;
use PinkCrab\Form\Builder\Element\Field\Attribute\Multiple;
use PinkCrab\Form\Builder\Element\Field\Input\Abstract_Input;
use function PinkCrab\FunctionConstructors\GeneralFunctions\{pipe};
use function PinkCrab\FunctionConstructors\Arrays\{filter, map, toString};
use PinkCrab\Form\Builder\Element\Field\Attribute\{Datalist, Disabled, Length, Placeholder, Read_Only, Required, Single_Value};
/**
 * Includes the following attributes:
 * - Datalist
 * - Single_Value
 */
class Email extends Abstract_Input {

	use Multiple, Datalist, Placeholder, Read_Only, Disabled, Length, Required;

	/**
	 * Sets the input type
	 *
	 * @var string
	 */
	protected $input_type = 'email';

	/** @inheritDoc */
	public function set_defaults(): void {
		// Set the pattern to match the date format.
		$this->sanitizer = static function( $value ) {
			if ( ! is_string( $value ) ) {
				return '';
			}

			return pipe(
				$value,
				\PinkCrab\FunctionConstructors\Strings\split( ',' ),
				map( 'trim' ),
				filter(
					static function( $email ) {
						return filter_var( $email, FILTER_VALIDATE_EMAIL );
					}
				),
				toString( ',' )
			);

			// Split the potential multiple emails into an array
			$emails = explode( ',', $value );
			$emails = array_map( 'trim', $emails );

			// Filter out any invalid emails.
			$emails = array_filter(
				$emails,
				static function( $email ) {
					return filter_var( $email, FILTER_VALIDATE_EMAIL );
				}
			);

			// Return the emails as a comma seperated string.
			return implode( ', ', $emails );
		};
	}

}
