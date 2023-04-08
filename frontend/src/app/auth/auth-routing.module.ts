import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';

const authRoutes: Routes = [
  {
    path: '', component: LoginComponent,
    children: [
      { path: 'login', component: LoginComponent, },
      { path: 'logout', redirectTo: '/auth/login' }, /* @todo cancellare valori di sessione */
    ]
  }
];

@NgModule({
  imports: [
    RouterModule.forChild(authRoutes)
  ],
  exports: [
    RouterModule
  ]
})
export class AuthRoutingModule {}
