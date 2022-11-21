<?php

declare( strict_types=1 );

/**
 * Form builder
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

use PinkCrab\Form\Form;
use Awurth\SlimValidation\Validator;
use PinkCrab\Form\Handler\Form_Handler;
use PinkCrab\Perique\Services\View\View;
use PinkCrab\Form\Validation\Form_Validator;
use Psr\Http\Message\ServerRequestInterface;
use PinkCrab\Perique\Interfaces\Inject_DI_Container;
use PinkCrab\Perique\Services\Container_Aware_Traits\Inject_DI_Container_Aware;

class Form_Service implements Inject_DI_Container {

	use Inject_DI_Container_Aware;

	/**
	 * Renders the form
	 *
	 * @var View
	 */
	private $renderer;

	/**
	 * Form Validators
	 *
	 * @var Form_Validator
	 */
	private $validator;

	/**
	 * The server request
	 *
	 * @var ServerRequestInterface
	 */
	private $request;


	/**
	 * Creates an instance of the Form Builder
	 *
	 * @param View $renderer
	 * @param Form_Validator $validator
	 * @param ServerRequestInterface $request
	 */
	public function __construct( View $renderer, Form_Validator $validator, ServerRequestInterface $request ) {
		$this->renderer  = $renderer;
		$this->validator = $validator;
		$this->request   = $request;
	}

	/**
	 * Creates a new form
	 *
	 * @param string $key
	 * @param string $action
	 * @param string $method
	 * @return Form
	 */
	public function create( string $key, string $method = 'POST', string $action = '' ): Form {
		return new Form( $key, $method, $action );
	}

	/**
	 * Get validator
	 *
	 * @return Form_Validator
	 */
	public function get_validator(): Form_Validator {
		return $this->validator;
	}

	/**
	 * Get renderer
	 *
	 * @return View
	 */
	public function get_renderer(): View {
		return $this->renderer;
	}

	/**
	 * Get the form handler
	 *
	 * @return Form_Handler
	 */
	public function get_handler(): Form_Handler {
		return new Form_Handler( $this->validator, $this->request );
	}
}
