import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddcategoryPage } from './addcategory.page';

describe('AddcategoryPage', () => {
  let component: AddcategoryPage;
  let fixture: ComponentFixture<AddcategoryPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddcategoryPage ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddcategoryPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
