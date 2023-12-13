import { forwardRef, useImperativeHandle, useState, useEffect } from 'react';
import dynamic from 'next/dynamic';
import useInputs from '@hooks/useInputs/useInputs';
import CommentListItem from '@components/commentListItem/commentListItem';
import InputTextarea from '@components/inputTextarea/inputTextarea';
import Button from '@components/button/button';
let BookListPagination = dynamic(() => import('@components/bookListPagination/bookListPagination'));
import {
  ButtonWrapper,
  CommentListComponent,
  Comments,
  Form,
  FormButton,
  FormInput,
  FormTitle,
  PaginantionWrapper,
  Title,
  TitleCount,
  TitleText,
} from '@components/commentList/commentList.styled';
import { useCallback } from 'react';

let inputsDefaults = {
  comment: '',
};

let errorsDefaults = {
  comment: '',
};

export default forwardRef(function CommentList(props, ref) {
  let { user, comments, onCommentSubmit, onCommentEdit, onCommentDeleteClick, onLoadMoreClick } = props;
  let { inputs, setInput, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);
  let [showPagination, setShowPagination] = useState(true);

  useImperativeHandle(ref, () => ({
    /* eslint-disable no-unused-labels */
    clearInput: () => {
      setInput('comment', '');
    },
  }));

  let handleCommentSubmit = useCallback(
    (e) => {
      e.preventDefault();

      import('joi').then((module) => {
        let joi = module.default;

        let schema = joi.object({
          comment: joi.string().required(),
          checkbox: joi.boolean().valid(true),
        });

        let validation = schema.validate(inputs, { abortEarly: false });

        if (validation.error) {
          let newErrorState = { ...errorsDefaults };

          validation.error.details.forEach((error) => {
            switch (error.type) {
              case 'string.empty':
                newErrorState[error.context.key] = 'Ez a mező nem lehet üres';
                break;

              default:
                newErrorState[error.context.key] = 'Hibás mező';
                break;
            }

            setErrors(newErrorState);
          });
        } else {
          setErrors({ ...errorsDefaults });
          onCommentSubmit(e, inputs.comment);
        }
      });
    },
    [inputs],
  );

  // Hiding pagination if fewer hits
  useEffect(() => {
    if (!comments?.pagination) return;

    if (comments.pagination.last_page && comments.pagination.current_page === 1) {
      if (showPagination) setShowPagination(false);
    } else {
      if (!showPagination) setShowPagination(true);
    }
  }, [comments]);

  function getItemCount(comments) {
    let currentCount = comments.pagination.current_page * comments.pagination.per_page;
    return currentCount > comments.pagination.total ? comments.pagination.total : currentCount;
  }

  if (!comments) return null;

  return (
    <CommentListComponent>
      <Title>
        <TitleText>Hozzászólások</TitleText>
        <TitleCount>({comments.pagination.total})</TitleCount>
      </Title>
      {user && user.type === 'user' && (
        <Form onSubmit={handleCommentSubmit}>
          <FormTitle>{user.customer.firstname ? user.customer.firstname : user.customer.email}:</FormTitle>
          <FormInput>
            <InputTextarea
              placeholder="Írj véleményt a könyvről..."
              height={80}
              name="input-book-comment"
              value={inputs.comment}
              error={errors.comment}
              onChange={(e) => setInput('comment', e.target.value)}
            ></InputTextarea>
          </FormInput>
          <FormButton>
            <ButtonWrapper>
              <Button buttonHeight="50px" buttonWidth="100%">
                Hozzászólok
              </Button>
            </ButtonWrapper>
          </FormButton>
        </Form>
      )}
      <Comments>
        {comments.comments.map(
          (comment) =>
            !comment.deleted && (
              <CommentListItem
                key={comment.id}
                comment={comment}
                userId={user?.customer.id}
                onCommentEdit={onCommentEdit}
                onCommentDeleteClick={onCommentDeleteClick}
              ></CommentListItem>
            ),
        )}
      </Comments>

      {showPagination && (
        <PaginantionWrapper>
          <BookListPagination
            itemCount={getItemCount(comments)}
            currentPage={comments.pagination.current_page}
            lastPage={comments.pagination.last_page}
            perPage={comments.pagination.per_page}
            totalItems={comments.pagination.total}
            itemLabel="Hozzászólás az összesből"
            buttonLabel="További hozzászólás betöltése"
            onClick={onLoadMoreClick}
            // loading={bookListQuery.isFetching}
          ></BookListPagination>
        </PaginantionWrapper>
      )}
    </CommentListComponent>
  );
});
