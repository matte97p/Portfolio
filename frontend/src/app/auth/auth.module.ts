import { NgModule } from '@angular/core';

import { LoginComponent } from './components/login/login.component';

import { CommonModule } from '@angular/common';
import { AuthRoutingModule } from './auth-routing.module';
import { ReactiveFormsModule } from '@angular/forms';
import { MessageModule } from 'primeng/message';
import { ButtonModule } from 'primeng/button';
import { InputTextModule } from 'primeng/inputtext';
import { CaptchaModule } from 'primeng/captcha';

import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from '../shared/utils/auth.interceptor';
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
    CaptchaModule,
  ],
  providers: [
    {
      provide : HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi   : true,
    },
    MessageService,
  ],
  exports: [

  ],
  bootstrap: [
    LoginComponent
  ]
})
export class AuthModule {}
