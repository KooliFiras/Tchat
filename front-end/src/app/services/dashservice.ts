import {Injectable} from '@angular/core';
import {Http, Headers} from '@angular/http';


@Injectable()
export class DashboardService {

  messageContent;
  actifsContent;
  groupContent;
  discussion;

  constructor(private _http:Http) {}

  inboxfn() {
    var headers = new Headers();
    var token= window.localStorage.getItem('auth_key');


    headers.append('Content-Type', 'application/json');
    headers.append('Authorization', 'Bearer '+ token);
    return new Promise((resolve) => {
      this._http.get('http://localhost/chatApp2/web/app_dev.php/api/messages/inbox', {headers: headers}).subscribe((data) => {
          if(data.json()) {this.messageContent=data.json()}
          resolve(false);
        }
      )
    })
  }

  actifsfn() {
    var headers = new Headers();
    var token= window.localStorage.getItem('auth_key');


    headers.append('content-Type', 'application/json');
    headers.append('authorization', 'Bearer '+ token);
    return new Promise((resolve) => {
      this._http.get('http://localhost/chatApp2/web/app_dev.php/api/users/user/is/online/action/getwho', {headers: headers}).subscribe((data) => {
          if(data.json()) {this.actifsContent=data.json()}
          resolve(false);
        }
      )
    })
  }


  discussionfn(threadId){


    var headers = new Headers();
    var token= window.localStorage.getItem('auth_key');


    headers.append('content-Type', 'application/json');
    headers.append('authorization', 'Bearer '+ token);
    return new Promise((resolve) => {
      this._http.get('http://localhost/chatApp2/web/app_dev.php/api/messages/discussion/'+threadId, {headers: headers}).subscribe((data) => {
          if(data.json()) {this.discussion=data.json()}
          resolve(false);
        }
      )
    })

  }


  sendMessage(usercreds) {

    var headers = new Headers();
    var  creds = JSON.stringify({
      threadId:     usercreds.threadId,
      senderId:  usercreds.senderId ,
      body: usercreds.body,
      participantId: usercreds.participantId

    }) ;

    headers.append('Content-Type', 'application/json');

    return new Promise((resolve) => {

      this._http.post('http://localhost/chatApp2/web/app_dev.php/api/messages/send', creds , {headers: headers}).subscribe((data) => {
          if(!data.json()) { this.discussionfn(usercreds.threadId)}
          resolve(false);
        }
      )

    })
  }















}






