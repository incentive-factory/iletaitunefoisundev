import {Component, Inject} from '@angular/core';
import {Validators} from "@angular/forms";
import {ControlsOf, FormControl, FormGroup} from "@ngneat/reactive-forms";
import {HttpErrorResponse} from "@angular/common/http";
import {ActivatedRoute, Route, Router} from "@angular/router";
import {Violation} from "../../../core/validator/violation";
import {Credentials, LOGIN_PROVIDER, LOGIN_TOKEN, LoginInterface} from "../../../core/security/authenticator.service";
import {VisitorGuard} from "../../../core/guard/visitor.guard";

@Component({
  selector: 'app-security-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
  providers: [LOGIN_PROVIDER]
})
export class LoginComponent {
  loginForm = new FormGroup<ControlsOf<Credentials>>({
    email: new FormControl('', [Validators.required, Validators.email]),
    password: new FormControl('', [Validators.required])
  });

  constructor(
    @Inject(LOGIN_TOKEN) private login: LoginInterface,
    private route: ActivatedRoute,
    private router: Router
  ) {
  }

  onSubmit() {
    this.login.login(this.loginForm.value).subscribe({
      next: () => {
        const params = this.route.snapshot.queryParams;
        const referrer = params.hasOwnProperty('return') ? params['return'] : '/';
        this.router.navigate([referrer]);
      },
      error: (error: HttpErrorResponse) => {
        this.loginForm.mergeErrors({violations: [<Violation>{message: error.error.message}]});
      }
    });

    return false;
  }
}

export const LOGIN_ROUTE: Route = {path: 'login', component: LoginComponent, canActivate: [VisitorGuard]};
