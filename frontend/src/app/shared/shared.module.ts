import { NgModule } from '@angular/core';

import { BreadcrumbComponent } from './components/breadcrumb/breadcrumb.component';
import { FullCalendarComponent } from './components/full-calendar/full-calendar.component';
import { PageNotFoundComponent } from './components/page-not-found/page-not-found.component';
import { MessagesComponent } from './components/messages/messages.component';

import { CommonModule } from '@angular/common';
import { HttpClientModule } from '@angular/common/http';
import { AuthModule } from '../auth/auth.module';
import { ToastModule } from 'primeng/toast';
import { BreadcrumbModule } from 'primeng/breadcrumb';
import { FullCalendarModule } from '@fullcalendar/angular';

import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from './utils/auth.interceptor';
import { MessagesService } from './services/messages.service';
import { AuthGuardService } from './services/auth-guard.service';

@NgModule({
  declarations: [
    BreadcrumbComponent,
    FullCalendarComponent,
    PageNotFoundComponent,
    MessagesComponent,
  ],
  imports: [
    CommonModule,
    HttpClientModule,
    AuthModule,
    ToastModule,
    BreadcrumbModule,
    FullCalendarModule,
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
    BreadcrumbComponent,
    FullCalendarComponent,
    PageNotFoundComponent,
    MessagesComponent,
  ]
})
export class SharedModule { }
