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

use PinkCrab\Form\Builder\Element\Field\Input\Abstract_Input;
use PinkCrab\Form\Builder\Element\Field\Attribute\{Disabled, Read_Only, Required, Single_Value};

class Button extends Abstract_Input {

	use Single_Value, Required, Read_Only, Disabled;

	/**
	 * Sets the input type
	 *
	 * @var string
	 */
	protected $input_type = 'button';

	/** @inheritDoc */
	public function set_defaults(): void {
		// Set the pattern to match the date format.
		$this->sanitizer = 'sanitize_text_field';
	}

	/**
	 * Text helper setter
	 *
	 * @param string $text
	 * @return self
	 */
	public function text( string $text ): self {
		$this->set_existing( $text );
		return $this;
	}


}
