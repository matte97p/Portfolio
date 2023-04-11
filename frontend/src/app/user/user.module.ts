import { NgModule } from '@angular/core';

import { ProfileComponent } from './components/profile/profile.component';

import { CommonModule } from '@angular/common';
import { UserRoutingModule } from './user-routing.module';
import { SharedModule } from '../shared/shared.module';

@NgModule({
  declarations: [
    ProfileComponent,
  ],
  imports: [
    CommonModule,
    UserRoutingModule,
    SharedModule,
  ],
  providers: [
  ],
  exports: [
  ],
  bootstrap: [
    ProfileComponent,
  ]
})
export class UserModule { }
