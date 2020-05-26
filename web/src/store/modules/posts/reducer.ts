/* tslint:disable:ter-indent */
/* eslint-disable indent */
import produce from "immer";

import { Actions, PostsAction } from "./actions";

import { Post } from "~/services/entities";

export type PostsState = {
  loading: boolean;
  error: boolean;
  posts: Post[];
};

const INITIAL_STATE: PostsState = {
  loading: true,
  error: false,
  posts: []
};

export default function reducer(state = INITIAL_STATE, action: PostsAction) {
  return produce(state, draft => {
    switch (action.type) {
      case Actions.FETCH_SUCCESS:
        draft.loading = false;
        draft.error = false;
        draft.posts = action.payload;

        break;
      case Actions.FETCH_FAIL:
        draft.loading = false;
        draft.error = true;

        break;
      case Actions.FETCH_REQUEST:
        draft.loading = true;
        draft.error = false;

        break;
    }
  });
}
