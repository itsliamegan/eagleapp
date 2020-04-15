import { Controller } from 'stimulus';

export default class MenuController extends Controller {
  static targets = ['menu'];

  toggle() {
    this.menuTarget.classList.toggle('menu-open');
  }
}
