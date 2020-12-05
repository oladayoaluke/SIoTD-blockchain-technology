import { CUSTOM_ELEMENTS_SCHEMA } from '@angular/core';
import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddgroupPage } from './addgroup.page';

describe('AddgroupPage', () => {
  let component: AddgroupPage;
  let fixture: ComponentFixture<AddgroupPage>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddgroupPage ],
      schemas: [CUSTOM_ELEMENTS_SCHEMA],
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddgroupPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
