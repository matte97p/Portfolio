import { NgModule } from '@angular/core';

import { AppComponent } from './components/app.component';
import { BreadcrumbComponent } from './components/breadcrumb/breadcrumb.component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found.component';
import { MessagesComponent } from './components/messages/messages.component';
import { AuthInterceptor } from './utils/auth.interceptor';

import { AppRoutingModule } from './app-routing.module';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AuthModule } from './auth/auth.module';
import { ToastModule } from 'primeng/toast';
import { BreadcrumbModule } from 'primeng/breadcrumb';

import { MessagesService } from './services/messages.service';
import { AuthGuardService } from './services/auth-guard.service';

@NgModule({
  declarations: [
    AppComponent,
    BreadcrumbComponent,
    PageNotFoundComponent,
    MessagesComponent,
  ],
  imports: [
    AppRoutingModule,
    HttpClientModule,
    BrowserAnimationsModule,
    AuthModule,
    ToastModule,
    BreadcrumbModule,
  ],
  providers: [
    {
      provide : HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi   : true,
    },
    MessagesService,
    AuthGuardService,
  ],
  exports: [
    MessagesComponent,
  ],
  bootstrap: [
    AppComponent
  ]
})
export class AppModule {
  constructor() {}
}
