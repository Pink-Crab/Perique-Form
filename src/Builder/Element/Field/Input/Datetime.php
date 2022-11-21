<?php

declare( strict_types=1 );

/**
 * Input date field
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

use DateTimeImmutable;
use PinkCrab\Form\Builder\Element\Field\Input\Abstract_Input;
use PinkCrab\Form\Builder\Element\Field\Attribute\{Range, Autocomplete, Placeholder, Datalist, Notification, Read_Only, Required, Single_Value, Step};

class Datetime extends Abstract_Input {

	use Range, Autocomplete, Placeholder, Datalist, Single_Value, Required, Read_Only;

	/** @inheritDoc */
	protected $input_type = 'datetime-local';

	/**
	 * Format used for the sanitizer.
	 * @var string
	 */
	protected $sanitizer_format = 'Y-m-d\TH:i';

	/**
	 * Sets the date formate used by the sanitizer.
	 *
	 * @param string $format
	 * @return self
	 */
	public function sanitizer_format( string $format ): self {
		$this->sanitizer_format = esc_attr( $format );
		return $this;
	}

	/** @inheritDoc */
	public function set_defaults(): void {
		// Set the pattern to match the datetime format.
		$this->sanitizer = function( $value ) {
			$date = DateTimeImmutable::createFromFormat( $this->sanitizer_format, \sanitize_text_field( $value ) );
			return $date ? $date->format( $this->sanitizer_format ) : '';
		};
	}

}
