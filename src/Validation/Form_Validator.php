<?php

declare( strict_types=1 );

/**
 * Form Validator Service.
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

namespace PinkCrab\Form\Validation;

use Respect\Validation\Validatable;
use Awurth\SlimValidation\Validator;
use Psr\Http\Message\ServerRequestInterface;

class Form_Validator {

	/** @var Validator */
	private $validator;

	/**
	 * Form_Validator constructor.
	 *
	 * @param Validator $validator
	 */
	public function __construct( Validator $validator ) {
		$this->validator = $validator;
	}

	/**
	 * Processes a ServerRequestInterface and returns a Form_Validator.
	 *
	 * @param ServerRequestInterface $request
	 * @param array<string, Validatable> $rules
	 * @return Form_Validator
	 */
	public function process_request( ServerRequestInterface $request, array $rules ): Form_Validator {
		$this->validator->request( $request, $rules );
		return $this;
	}

	/**
	 * Gets any errors from the validator.
	 *
	 * @return array<string, string>
	 */
	public function get_errors(): array {
		return $this->validator->getErrors();
	}
}
