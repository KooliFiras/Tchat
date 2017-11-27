import {Component} from '@angular/core';
import {Router} from '@angular/router';

import {AuthService} from '../../services/authservice';

@Component({
  selector: 'signup-app',
  templateUrl: './signup.component.html',
  providers: [ AuthService ]
})

export class SignupComponent {
  localUser = {
    email:'',
    username: '',
    password_first: '',
    password_second:''
  }


  constructor(private _service:AuthService, private _router: Router) {}

  signup(){
    this._service.signupfn(this.localUser).then((res) => {
      if(res)
        this._router.navigate(['/dashboard']);
      else
        console.log(res);
    })
  }

  clearfields() {
    this.localUser.email='';
    this.localUser.username = '';
    this.localUser.password_first = '';
    this.localUser.password_second = '';
  }


}
