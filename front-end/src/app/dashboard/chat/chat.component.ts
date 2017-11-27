import { Component, EventEmitter, OnInit } from '@angular/core';
import { JwtHelper } from 'angular2-jwt';
import {Router, ActivatedRoute, ParamMap} from '@angular/router';
import {Location} from '@angular/common';
import 'rxjs/add/operator/switchMap';
import {MaterializeAction} from 'angular2-materialize';
import {AuthService} from "../../services/authservice";
import {DashboardService} from "../../services/dashservice";
import { Ng2UploaderModule } from 'ng2-uploader';
import 'rxjs/add/operator/delay';




@Component({
  selector: 'app-root',
  styleUrls: [],
  templateUrl:'./chat.component.html',
  providers: [DashboardService,AuthService]
})
export class ChatComponent implements OnInit {

discussion:any;
  token:string;
  jwtHelper: JwtHelper = new JwtHelper();
  localUser:any;
  participant:any;

  creds: Object ={
    threadId :'',
    senderId:'',
    body:'',
    participantId:''
  }


  uploadFile: any;
  hasBaseDropZoneOver: boolean = false;
  options: Object = {
    url: 'http://localhost/upload',

  };
  sizeLimit = 2000000;


  constructor( private _service:DashboardService,private _location: Location,  private route: ActivatedRoute,
               private router: Router,private _service2:AuthService){

  }

 ngOnInit(){

   this.showDiscussion()


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


  showDiscussion(){



    let id = this.route.snapshot.paramMap.get('id');

    this._service.discussionfn(id).then(() => {

      if (this._service.discussion.length!=0)
      {
        this.discussion= this._service.discussion;
        this.getActifUser();
      }
    })

  }


  getActifUser(){
    this.token = window.localStorage.getItem('auth_key');
    let id= this.jwtHelper.decodeToken(this.token).userId;
    this._service2.getuserfn(id).then(() =>{
      this.localUser=this._service2.localUser;
      console.log(this.localUser)
    })

  }


  getParticipantInfo(){

      this.discussion[0].metadata.forEach(function(participant) {
        if (participant.username!= this.localUser.username){
          this.participant=participant;
          console.log('participant:', this.participant)

        }
      });

  }


  sendMessage(){

      let id = this.route.snapshot.paramMap.get('id');

      this.creds['threadId']=id;

      this.creds['senderId']= this.localUser.id;
      this.creds['participantId']= this.participant.id;
      console.log(this.creds)

  }


  handleUpload(data): void {
    if (data && data.response) {
      data = JSON.parse(data.response);
      this.uploadFile = data;
    }
  }

  fileOverBase(e:any):void {
    this.hasBaseDropZoneOver = e;
  }

  beforeUpload(uploadingFile): void {
    if (uploadingFile.size > this.sizeLimit) {
      uploadingFile.setAbort();
      alert('File is too large');
    }
  }



}
