import { Controller } from 'stimulus';
import Turbolinks from 'turbolinks';

export default class FormController extends Controller {
  static targets = ['submitButton'];

  async submit(event) {
    event.preventDefault();

    const submittingText = this.data.get('submitting-text') || 'Saving...';
    this.originalSubmitText = this.submitButtonTarget.textContent;
    this.submitButtonTarget.textContent = submittingText;
    this.submitButtonTarget.disabled = true;

    const form = event.target;
    const data = new FormData(form);
    const url = form.action || window.location.path;
    const method = form.method.toUpperCase() || 'GET';

    const res = await fetch(url, {
      method,
      body: data,
    });

    if (!res.ok) {
      this.submitButtonTarget.textContent = this.originalSubmitText;
      this.submitButtonTarget.disabled = false;
    } else {
      let url = window.location.path;

      if (this.data.has('redirectTo')) {
        url = this.data.get('redirectTo');
      }

      Turbolinks.visit(url);
    }
  }
}
