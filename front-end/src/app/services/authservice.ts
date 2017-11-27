import {Injectable} from '@angular/core';
import {Http, Headers} from '@angular/http';
import 'rxjs/add/operator/toPromise';
import 'rxjs/add/operator/catch';
import {Observable} from "rxjs/Observable";

@Injectable()
export class AuthService {

  error:string='';

  isLogged:boolean=false;

  localUser:{
    confirmation_token:string,
    email:string,
    email_canonical:string,
    enabled:string,
    groups:any,
    id:number,
    last_activity:string,
    last_login:string
    password:string,
    password_requested_at:string,
    roles:any,
    username:string,
    username_canonical:string,
  };


  constructor(private _http:Http) {}

  loginfn(usercreds):Promise<any> {
    var headers = new Headers();
    var  creds = JSON.stringify({
      username:  usercreds.username ,
      password : usercreds.password
    }) ;

    headers.append('Content-Type', 'application/json');

    return this._http.post('http://localhost/chatApp2/web/app_dev.php/api/users/login', creds , {headers: headers})
                      .toPromise()
                      .then(response => {
                        if (response.json().token){
                          window.localStorage.setItem('auth_key', response.json().token);
                          this.isLogged=true;
                          return true;
                        }else {
                          this.error=response.json()['message']
                          console.log('error  :' ,this.error)

                        }

                      }).catch(this.handleError)

                                }


  private handleError(error: any): Promise<any> {
    console.error('An error occurred', error); // for demo purposes only
    return Promise.reject(error.message || error);
                                                  }


  signupfn(usercreds) {

    var headers = new Headers();
    var  creds = JSON.stringify({
      email:     usercreds.email,
      username:  usercreds.username ,
      plainPassword:  {
        first:  usercreds.password_first ,
        second: usercreds.password_second
      }
    }) ;

    headers.append('Content-Type', 'application/json');

    return new Promise((resolve) => {



      this._http.post('http://localhost/chatApp2/web/app_dev.php/api/users/register', creds , {headers: headers})
            .toPromise()
            .then(response => {
              if (response.json().token){
                window.localStorage.setItem('auth_key', response.json().token);
                this.isLogged=true;
                return true;
              }else {
                this.error=response.json()['message']
                console.log('error  :' ,this.error)

              }

            }).catch(this.handleError)

    })
                    }

  getuserfn(id) {
          var headers = new Headers();
          var token= window.localStorage.getItem('auth_key');


          headers.append('Content-Type', 'application/json');
          headers.append('Authorization', 'Bearer '+ token);
          return new Promise((resolve) => {
            this._http.get('http://localhost/chatApp2/web/app_dev.php/api/users/profile/'+id, {headers: headers}).subscribe((data) => {
                if(data.json()) { this.localUser =data.json();

                }
                resolve(false);
              }
            )
          })
                }

  setUserEmail(id){

          var headers = new Headers();
          var token= window.localStorage.getItem('auth_key');


          headers.append('Content-Type', 'application/json');
          headers.append('Authorization', 'Bearer '+ token);

          var  creds = JSON.stringify({email:this.localUser.email}) ;

          return new Promise((resolve) => {
            this._http.patch('http://localhost/chatApp2/web/app_dev.php/api/users/profile/'+id, creds , {headers: headers}).subscribe((data) => {
                if(!data.json()) { this.getuserfn(id) ;}
                resolve(false);
              }
            )
          })
                  }





}
