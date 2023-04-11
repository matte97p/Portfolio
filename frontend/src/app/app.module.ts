import { NgModule } from '@angular/core';

import { AppComponent } from './components/app.component';

import { AppRoutingModule } from './app-routing.module';
import { SharedModule } from './shared/shared.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AuthModule } from './auth/auth.module';
import { HomepageModule } from './homepage/homepage.module';
import { UserModule } from './user/user.module';
import { CommonModule } from '@angular/common';
import { ButtonModule } from 'primeng/button';

import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './shared/utils/auth.interceptor';

@NgModule({
  declarations: [
    AppComponent,
  ],
  imports: [
    CommonModule,
    AppRoutingModule,
    SharedModule,
    BrowserAnimationsModule,
    AuthModule,
    HomepageModule,
    UserModule,
    ButtonModule,
  ],
  providers: [
    {
      provide : HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi   : true,
    },
  ],
  exports: [
  ],
  bootstrap: [
    AppComponent,
  ]
})
export class AppModule {
  constructor() {}
}
