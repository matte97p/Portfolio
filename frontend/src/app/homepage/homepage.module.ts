import { NgModule } from '@angular/core';

import { HomepageComponent } from './components/homepage/homepage.component';

import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { HomepageRoutingModule } from './homepage-routing.module';
import { ButtonModule } from 'primeng/button';

import { HTTP_INTERCEPTORS } from '@angular/common/http';
import { AuthInterceptor } from '../shared/utils/auth.interceptor';

@NgModule({
  declarations: [
    HomepageComponent,
  ],
  imports: [
    CommonModule,
    HomepageRoutingModule,
    SharedModule,
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
    HomepageComponent,
  ]
})
export class HomepageModule { }
