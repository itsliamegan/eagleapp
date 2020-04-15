import { Controller } from 'stimulus';
import Turbolinks from 'turbolinks';

export default class HeaderController extends Controller {
  async logout() {
    const res = await fetch('/logout', {
      method: 'POST',
    });

    if (!res.ok) {
      const message = await res.text();
      console.warn(message);
      // append message
    } else {
      Turbolinks.visit('/login');
    }
  }
}
