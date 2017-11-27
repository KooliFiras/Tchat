import {Component, OnInit} from '@angular/core';
import { JwtHelper } from 'angular2-jwt';

import {Router} from '@angular/router';
import {DashboardService} from '../services/dashservice';
import {Actif} from  './actifs/actif';
import {Message} from  './messages/message';
import {AuthService} from "../services/authservice";

@Component({
  templateUrl: './dashboard.component.html',
  providers: [AuthService,DashboardService,]
})

export class DashboardComponent implements OnInit{

  messages:Message[];
  groups:string;
  actifs:Actif[];
  actifNb:number=0;  // nombre of actifs person
  activate:boolean=false; // activate the tab of actifs
  token:string;
  jwtHelper: JwtHelper = new JwtHelper();
  localUser:any;


  constructor(private _router:Router,private _service:DashboardService,private service2:AuthService){



  }
  ngOnInit():void{
    this.showMessages();
    this.showActifs();
    this.getActifUser()

  }

  showMessages(){

    this._service.inboxfn().then(() => {
      if (this._service.messageContent.length!=0)
      {
        //console.log(this._service.messageContent);
        this.messages= this._service.messageContent;
        console.log(this.messages);
      }
                                      })
             }

  showActifs(){
    this._service.actifsfn().then(() => {
      if(this._service.actifsContent.length!=0){
        this.actifs=this._service.actifsContent;
        this.actifNb=this.actifs.length-1;
       // console.log(this.actifs);
      }
    })
  }

  showGroups(){

  }


  showProfile(){
    this._router.navigate(['/profile'])
  }

  logout() {
    window.localStorage.removeItem('auth_key');
    this._router.navigate(['']);
  }

  go(){
    this.activate=true;
  }

  chat(id){
    this._router.navigate(['/chat/'+id])
                }

  getActifUser(){
    this.token = window.localStorage.getItem('auth_key');
    let id= this.jwtHelper.decodeToken(this.token).userId;
    this.service2.getuserfn(id).then(() =>{
      this.localUser=this.service2.localUser
      //console.log(this.localUser)
    })

  }

}

