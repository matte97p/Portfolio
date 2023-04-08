import { NgModule } from '@angular/core';

import { HomepageComponent } from './components/homepage/homepage.component';

import { CommonModule } from '@angular/common';

@NgModule({
  declarations: [
    HomepageComponent
  ],
  imports: [
    CommonModule
  ],
  providers: [
  ],
  exports: [
  ],
  bootstrap: [
    HomepageComponent
  ]
})
export class HomepageModule { }
