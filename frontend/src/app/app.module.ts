import { NgModule } from '@angular/core';

import { AppComponent } from './components/app.component';

import { AppRoutingModule } from './app-routing.module';
import { SharedModule } from './shared/shared.module';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AuthModule } from './auth/auth.module';
import { HomepageModule } from './homepage/homepage.module';
import { UserModule } from './user/user.module';
import { CommonModule } from '@angular/common';



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
  ],
  providers: [
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
