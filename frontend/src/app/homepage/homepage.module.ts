import { NgModule } from '@angular/core';

import { HomepageComponent } from './components/homepage/homepage.component';

import { CommonModule } from '@angular/common';
import { SharedModule } from '../shared/shared.module';
import { HomepageRoutingModule } from './homepage-routing.module';

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
  ],
  exports: [
  ],
  bootstrap: [
    HomepageComponent,
  ]
})
export class HomepageModule { }
