import { NgModule } from '@angular/core';

import { LoginComponent } from './components/login/login.component';

import { CommonModule } from '@angular/common';
import { AuthRoutingModule } from './auth-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { MessageModule } from 'primeng/message';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';

import { MessageService } from 'primeng/api';

@NgModule({
  declarations: [
    LoginComponent,
  ],
  imports: [
    CommonModule,
    AuthRoutingModule,
    ReactiveFormsModule,
    MessageModule,
    ButtonModule,
    InputTextModule,
  ],
  providers: [
    MessageService,
  ],
  exports: [

  ],
  bootstrap: [
    LoginComponent
  ]
})
export class AuthModule {}
