import {Component} from '@angular/core';
import {Router} from '@angular/router';

import {AuthService} from '../../services/authservice';

@Component({
  selector: 'login-app',
  templateUrl: './login.component.html',
  providers: [AuthService]
})

export class LoginComponent {
  localUser = {
    username: '',
    password: ''
  }


  constructor(private _service:AuthService, private _router: Router) {

  }

  login():void {
    this._service.loginfn(this.localUser).then((res) => {
      if(res)
        this._router.navigate(['/dashboard']);
      else
        console.log(res);
    })
  }


  clearvalues() {
    this.localUser.username = '';
    this.localUser.password = '';
  }

}
