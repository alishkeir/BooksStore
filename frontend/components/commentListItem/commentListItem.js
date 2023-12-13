import { useState, useCallback, useEffect } from 'react';
import { format, parseISO } from 'date-fns/fp';
import Button from '@components/button/button';
import useInputs from '@hooks/useInputs/useInputs';
import InputTextarea from '@components/inputTextarea/inputTextarea';
import {
  Action,
  ActionWrapper,
  Actions,
  ButtonWrapper,
  CommentListItemComponent,
  Footer,
  InputWrapper,
  Meta,
  Text,
  TextEdit,
  User,
  UserName,
  UserNameId,
  UserNameOwn,
  UserPhoto,
} from '@components/commentListItem/commentListItem.styled';

let inputsDefaults = {
  comment: '',
};

let errorsDefaults = {
  comment: '',
};

export default function CommentListItem({ comment, userId, onCommentEdit, onCommentDeleteClick }) {
  let parsedISODate = parseISO(comment.published_at);

  let [editOpen, setEditOpen] = useState(false);
  let { inputs, setInput, errors, setErrors } = useInputs(inputsDefaults, errorsDefaults);

  let handleEditClick = useCallback(() => {
    setEditOpen(!editOpen);
  });

  let handleCommentSubmit = useCallback((e, id, comment) => {
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
          console.log(errors);
        });
      } else {
        setErrors({ ...errorsDefaults });
        onCommentEdit(id, comment);
        setEditOpen(false);
      }
    });
  });

  useEffect(() => {
    inputs.comment !== comment.comment && setInput('comment', comment.comment);
  }, [editOpen]);

  return (
    <CommentListItemComponent>
      <User>
        <UserPhoto></UserPhoto>
        <UserName>
          <UserNameId>{comment.writer}</UserNameId>
          {comment.customer_id === userId && <UserNameOwn>(saját hozzászólásod)</UserNameOwn>}
        </UserName>
      </User>
      {!editOpen && <Text>{comment.comment}</Text>}
      {editOpen && (
        <TextEdit>
          <InputWrapper>
            <InputTextarea
              placeholder="Írj véleményt a könyvről..."
              height={80}
              name="input-book-comment"
              value={inputs.comment}
              error={errors.comment}
              onChange={(e) => setInput('comment', e.target.value)}
            ></InputTextarea>
          </InputWrapper>
          <ActionWrapper>
            <ButtonWrapper>
              <Button buttonHeight="50px" buttonWidth="100%" onClick={(e) => handleCommentSubmit(e, comment.id, inputs.comment)}>
                Módosítom
              </Button>
            </ButtonWrapper>
          </ActionWrapper>
        </TextEdit>
      )}
      <Footer>
        <Meta>{parsedISODate && format('yyyy. MM. dd. HH:mm', parsedISODate)}</Meta>
        {comment.customer_id === userId && (
          <Actions>
            <Action onClick={() => onCommentDeleteClick(comment.id)}>Törlés</Action>
            <Action onClick={handleEditClick}>{editOpen ? 'Szerkesztés bezárása' : 'Szerkesztés'}</Action>
          </Actions>
        )}
      </Footer>
    </CommentListItemComponent>
  );
}
