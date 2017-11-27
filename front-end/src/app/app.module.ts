import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { MaterializeModule } from 'angular2-materialize';
import { HttpModule } from '@angular/http';
import { FormsModule }   from '@angular/forms';
import { Ng2UploaderModule } from 'ng2-uploader';
import {JwtHelper} from 'angular2-jwt';


import { AppRoutingModule }     from './app-routing.module';



import { AppComponent } from './app.component';
import {LoginComponent} from "./auth/login/login.component";
import {SignupComponent} from "./auth/signup/signup.component";
import {DashboardComponent} from "./dashboard/dashboard.component";
import {AuthService} from "./services/authservice";
import {DashboardService} from "./services/dashservice";
import {AuthComponent} from "./auth/auth.component";
import {ProfileComponent} from "./dashboard/profile/profile.component";
import {ChatComponent} from "./dashboard/chat/chat.component";



@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    SignupComponent,
    AuthComponent,
    ProfileComponent,
    DashboardComponent,
    ChatComponent,

  ],
  imports: [
    BrowserModule,
    MaterializeModule,
    HttpModule,
    FormsModule,
    AppRoutingModule,
    Ng2UploaderModule,
  ],
  providers: [
    AuthService,
    DashboardService,

  ],
  bootstrap: [AppComponent]
})
export class AppModule { }
