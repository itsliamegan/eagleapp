import { Controller } from 'stimulus';
import Turbolinks from 'turbolinks';

export default class LoginController extends Controller {
  static targets = ['loginButton'];

  connect() {
    this.loginButtonTarget.disabled = true;
    this.script = document.createElement('script');
    this.script.src = 'https://apis.google.com/js/platform.js';
    this.script.onload = this.handleScriptLoaded.bind(this);
    document.head.appendChild(this.script);
  }

  disconnect() {
    this.script.remove();
  }

  handleScriptLoaded() {
    this.loginButtonTarget.disabled = false;
    window.gapi.load('auth2', () => {
      window.gapi.auth2
        .init({ client_id: GOOGLE_CLIENT_ID })
        .then(this.handleGoogleClientInitialized.bind(this));
    });
  }

  handleGoogleClientInitialized(auth2) {
    auth2.attachClickHandler(
      'login-button',
      { prompt: 'select_account' },
      this.login.bind(this)
    );
  }

  updateButton() {
    this.loginButtonTarget.textContent = 'Logging In...';
    this.loginButtonTarget.disabled = true;
  }

  async login(googleUser) {
    const token = googleUser.getAuthResponse().id_token;

    const res = await fetch('/login', {
      method: 'POST',
      credentials: 'include',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ token }),
    });

    if (!res.ok) {
      this.loginButtonTarget.textContent = 'Login';
      this.loginButtonTarget.disabled = false;

      const message = await res.text();
      console.warn(message);
      // append message
    } else {
      const date = new Date();

      const year = date.getFullYear();
      const month = date.getMonth() + 1;
      const day = date.getDate();

      const formatted = `${year}-${month}-${day}`;

      Turbolinks.visit(`/schedules/${formatted}`);
    }
  }
}
