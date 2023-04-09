import { NgModule } from '@angular/core';

import { HomepageComponent } from './components/homepage/homepage.component';

import { CommonModule } from '@angular/common';
import { AppModule } from '../app.module';

@NgModule({
  declarations: [
    HomepageComponent,
  ],
  imports: [
    CommonModule,
    AppModule,
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
