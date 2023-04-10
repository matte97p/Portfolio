import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomepageComponent } from './components/homepage/homepage.component';
import { AuthGuardService } from '../shared/services/auth-guard.service';

const homepageRoutes: Routes = [
  {
    path: '', component: HomepageComponent,
    canActivate: [AuthGuardService],
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(homepageRoutes)
  ],
  exports: [
    RouterModule
  ]
})
export class HomepageRoutingModule {}
