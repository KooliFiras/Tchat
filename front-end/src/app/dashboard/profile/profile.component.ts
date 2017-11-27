import { Component, EventEmitter, OnInit } from '@angular/core';
import {MaterializeAction} from 'angular2-materialize';
import { JwtHelper } from 'angular2-jwt';
import {Router} from '@angular/router';
import {Location} from '@angular/common';
import {AuthService} from "../../services/authservice";

@Component({
  selector: 'app-root',
  styleUrls: [],
  templateUrl:'./profile.component.html',
  providers: [AuthService]
})
export class ProfileComponent implements OnInit {


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

  token;

  jwtHelper: JwtHelper = new JwtHelper();

  constructor( private _service:AuthService,private _location: Location){

  }

  ngOnInit(){
    this.getUser();
            }

  modalActions = new EventEmitter<string|MaterializeAction>();

  openModal() {
    this.modalActions.emit({action:"modal",params:['open']});
  }

  closeModal() {
    this.modalActions.emit({action:"modal",params:['close']});
  }

  backClicked() {
    this._location.back();
                  }

   getUser(){
    this.token = window.localStorage.getItem('auth_key');
    let id= this.jwtHelper.decodeToken(this.token).userId;
    this._service.getuserfn(id).then(() =>{
      this.localUser=this._service.localUser
    })
          }

 setuserEmail(){
    this.token = window.localStorage.getItem('auth_key');
    let id= this.jwtHelper.decodeToken(this.token).userId;
    this._service.setUserEmail(id).then(() =>{
      this.localUser=this._service.localUser
    })

     setTimeout(()=>{    //<<<---    using ()=> syntax
       this.closeModal()
     },3000);

 }

}
