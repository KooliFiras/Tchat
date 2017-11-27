import { NgModule }             from '@angular/core';
import { RouterModule, Routes } from '@angular/router';


import { AuthComponent }   from './auth/auth.component';
import { DashboardComponent }   from './dashboard/dashboard.component';
import { ProfileComponent }   from './dashboard/profile/profile.component';
import { ChatComponent }   from './dashboard/chat/chat.component';

const routes: Routes = [

  { path: '',  component: AuthComponent },
  { path: 'dashboard',  component: DashboardComponent },
  { path: 'profile',  component: ProfileComponent },
  { path: 'chat/:id',  component: ChatComponent },
];

@NgModule({
  imports: [ RouterModule.forRoot(routes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
