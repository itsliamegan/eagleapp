import { Application } from 'stimulus';
import Turbolinks from 'turbolinks';
import LoginController from './controllers/LoginController';
import HeaderController from './controllers/HeaderController';
import FormController from './controllers/FormController';
import MenuController from './controllers/MenuController';

const app = Application.start();

app.register('login', LoginController);
app.register('header', HeaderController);
app.register('form', FormController);
app.register('menu', MenuController);

Turbolinks.start();
