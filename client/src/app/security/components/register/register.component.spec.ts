import {createRoutingFactory, SpectatorRouting, SpyObject} from "@ngneat/spectator";
import {HttpClientTestingModule} from "@angular/common/http/testing";
import {RouterTestingModule} from "@angular/router/testing";
import {ReactiveFormsModule} from "@angular/forms";
import {of, throwError} from "rxjs";
import {Router} from "@angular/router";
import {HttpErrorResponse} from "@angular/common/http";
import {RegisterComponent} from "./register.component";
import {STORAGE_MANAGER_PROVIDER} from "../../../shared/storage/storage-manager.service";
import {Register, REGISTER_TOKEN, Registration, User} from "../../contracts/register";
import {SESSION_PROVIDER} from "../../contracts/session";
import {UserConsumer} from "../../consumers/user-consumer.service";

describe('Login component', () => {
  let spectator: SpectatorRouting<RegisterComponent>;
  let register: SpyObject<Register>

  const createComponent = createRoutingFactory({
    component: RegisterComponent,
    componentMocks: [UserConsumer],
    componentProviders: [
      SESSION_PROVIDER,
      {provide: REGISTER_TOKEN, useExisting: UserConsumer},
      STORAGE_MANAGER_PROVIDER
    ],
    imports: [HttpClientTestingModule, RouterTestingModule, ReactiveFormsModule]
  });

  beforeEach(() => {
    spectator = createComponent();
    register = spectator.inject(UserConsumer, true);
  });

  it('should submit form register and redirect to login', () => {
    const user: User = {
      id: 1,
      email: 'user@email.com',
      forgottenPasswordToken: null
    };
    const router = spectator.inject(Router, true);
    register.register.and.returnValue(of(user));
    spectator.typeInElement('user@email.com', '.input-email');
    spectator.typeInElement('Password123!', '.input-plainPassword');
    spectator.click('.form-submit');
    const registration: Registration = {
      email: 'user@email.com',
      plainPassword: 'Password123!'
    };
    expect(register.register).toHaveBeenCalledWith(registration);
    expect(router.navigate).toHaveBeenCalledWith(['/login']);
  });

  it('should submit form login and raise violations', () => {
    spyOn(spectator.component.registerForm, 'mergeErrors');
    register.register.and.returnValue(throwError(() => new HttpErrorResponse({
      error: {message: 'Error'}
    })));
    spectator.typeInElement('user@email.com', '.input-email');
    spectator.typeInElement('Password123!', '.input-plainPassword');
    spectator.click('.form-submit');
    const registration: Registration = {
      email: 'user@email.com',
      plainPassword: 'Password123!'
    };
    expect(register.register).toHaveBeenCalledWith(registration);
    expect(spectator.component.registerForm.mergeErrors).toHaveBeenCalled();
  });
});
