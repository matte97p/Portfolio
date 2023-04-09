import { NgModule } from '@angular/core';

import { AppComponent } from './components/app.component';
import { BreadcrumbComponent } from './shared/components/breadcrumb/breadcrumb.component';
import { PageNotFoundComponent } from './shared/components/page-not-found/page-not-found.component';
import { MessagesComponent } from './shared/components/messages/messages.component';
import { FullCalendarComponent } from './shared/components/full-calendar/full-calendar.component';

import { AuthInterceptor } from './shared/utils/auth.interceptor';

import { AppRoutingModule } from './app-routing.module';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { AuthModule } from './auth/auth.module';
import { ToastModule } from 'primeng/toast';
import { BreadcrumbModule } from 'primeng/breadcrumb';

import { MessagesService } from './shared/services/messages.service';
import { AuthGuardService } from './shared/services/auth-guard.service';
import { FullCalendarModule } from '@fullcalendar/angular';

@NgModule({
  declarations: [
    AppComponent,
    BreadcrumbComponent,
    PageNotFoundComponent,
    MessagesComponent,
    FullCalendarComponent,
  ],
  imports: [
    AppRoutingModule,
    HttpClientModule,
    BrowserAnimationsModule,
    AuthModule,
    ToastModule,
    BreadcrumbModule,
    FullCalendarModule
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
    FullCalendarComponent,
  ],
  bootstrap: [
    AppComponent,
  ]
})
export class AppModule {
  constructor() {}
}
